<?php
require 'recipe/common.php';

serverList('servers.yml');

set('shared_dirs', ['ws/logs']);

set('shared_files', ['ws/app/config/prod.php']);

task('deploy:writable_folders', function () {
	run('chmod 777 {{release_path}}/ws/logs');
})->desc('Configure Database');

task('deploy:bower_install', function () {
    run('cd {{release_path}} && bower install');
})->desc('Install Bower');

task('deploy:cleanup', function () {
    $releases = env('releases_list');
    $keep = get('keep_releases');
    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }
    foreach ($releases as $release) {
        run("rm -rf {{deploy_path}}/releases/$release");
    }
})->desc('Cleaning up old releases');

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable_folders',
    'deploy:vendors',
    'deploy:bower_install',
    'deploy:cleanup'
]);

set('repository', 'https://github.com/skurty/nutrition.git');