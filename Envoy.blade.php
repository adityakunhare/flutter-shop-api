@setup
    require __DIR__.'/vendor/autoload.php'; 
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $npm = isset($npm) ? $npm : 'true';
    $npm = filter_var($npm, FILTER_VALIDATE_BOOLEAN);

    if(!isset($env)){
        $env = 'staging';
    }

    if($env == 'production'){
        $env = 'production';
    }

    // Local build directory (on your laptop)
    $localRoot = env('LOCAL_ROOT_PROJECT');

    
    // ENVIRONMENT CONFIG
    $configs = [
        'staging' => [
            'server'        => env('STG_SERVER'),
            'port'          =>  env('STG_PORT'),
            'branch'        => env('STG_BRANCH'),
            'remoteProject' => env('STG_REMOTE_PROJECT'),
            'remotePublic'  => env('STG_REMOTE_PUBLIC') 
        ],

        'production' => [
            'server'        => env('PRD_SERVER'),
            'port'          =>  env('PRD_PORT'),
            'branch'        => env('PRD_BRANCH'),
            'remoteProject' => env('PRD_REMOTE_PROJECT'),
            'remotePublic'  => env('PRD_REMOTE_PUBLIC') 
        ],
    ];

    // SELECT CONFIG BASED ON ENV
    $server        = $configs[$env]['server'];
    $port          = $configs[$env]['port'];
    $branch        = $configs[$env]['branch'];
    $remoteProject = $configs[$env]['remoteProject'];
    $remotePublic  = $configs[$env]['remotePublic'];

    // Zero-downtime structure
    $release   = 'release_' . date('YmdHis');
    $remoteTemp = "{$remotePublic}/{$release}";
    $current    = "{$remotePublic}/build";
    $previous   = "{$remotePublic}/build_prev";

@endsetup

@servers(['web' => $server .' -p '.$port, 'local' => '127.0.0.1'])


{{-- ===========================================================
     1. GIT PUSH & PULL ORIGIN 
   =========================================================== --}}
@task('push', ['on' => 'local'])
    echo "Switching to {{ $branch }}...";
    cd {{ $localRoot }}

    echo "Pushing {{$branch}} to origin...";
    git push origin {{ $branch }}
@endtask

@task('pull', ['on' => 'web'])
    echo "Switching to {{ $branch }}...";
    cd {{ $remoteProject }}

    echo "Pulling changes from origin {{$branch}}...";
    git pull origin {{$branch}};
@endtask

{{-- ===========================================================
      LOCAL BUILD & UPLOAD (npm build and move)
   =========================================================== --}}
@task('build', ['on' => 'local'])
    echo "Removing older build...";
    rm -rf {{ $localRoot }}/public/build

    echo "Building frontend...";
    npm run build

    echo "Creating release folder on {{ $env }} server...";
    ssh -p {{ $port }} {{ $server }}  "mkdir -p {{ $remoteTemp }} && rm -rf {{ $remoteProject }}/public/build";

    echo "Uploading build to {{ $remoteTemp }}...";
    rsync -avz -e "ssh -p {{ $port }}" "{{ $localRoot }}/public/build/" "{{ $server }}:{{ $remoteTemp }}"
    rsync -avz -e "ssh -p {{ $port }}" "{{ $localRoot }}/public/build/" "{{ $server }}:{{ $remoteProject }}/public/build"
@endtask


{{-- ===========================================================
     MOVE & SWAP  (BUILD) 
   =========================================================== --}}
@task('swap', ['on' => 'web'])
    echo "Backing up current build...";
    rm -rf {{ $previous }}
    mv {{ $current }} {{ $previous }} 

    echo "Activating new release...moving {{$remoteTemp}} to {{$current }}";
    mv {{ $remoteTemp }} {{ $current }}
@endtask

{{-- ===========================================================
        MIGRATE
   =========================================================== --}}
@task('migrate' , ['on' => 'web'])

    echo "composer dumping autoload...";
    cd {{ $remoteProject }}
    composer install
    composer dump-autoload

    echo "Migrating database...";
    php {{ $remoteProject }}/artisan migrate --force 
@endtask


{{-- ===========================================================
     OPTIMIZE (Laravel)
   =========================================================== --}}
@task('optimize' , ['on' => 'web'])
    echo "Clearing Laravel caches...";
    php {{ $remoteProject }}/artisan optimize:clear

    echo "Rebuilding caches...";
    php {{ $remoteProject }}/artisan optimize
    
    echo "Optimization for {{ $env }} complete.";
@endtask



{{-- ===========================================================
     ROLLBACK
   =========================================================== --}}
@task('rollback', ['on' => 'web'])
    echo "Rollback on environment: {{ $env }}";

    echo "Restoring previous build...";
    rm -rf {{ $current }}
    mv {{ $previous }} {{ $current }}

    echo "Rollback done.";
@endtask


{{-- ===========================================================
     FULL DEPLOY PIPELINE
   =========================================================== --}}
@story('deploy')
    push
    pull    
    @if($npm)
        build
        swap
    @endif
    {{-- migrate --}}
    optimize
@endstory
