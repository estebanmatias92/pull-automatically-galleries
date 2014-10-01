module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    var commandDb = function(command, database, environment) {
        command += typeof(database) === 'undefined' ? '' : (' --database="' + database + '"');
        command += typeof(environment) === 'undefined' ? '' : (' --env="' + environment + '"');

        return command;
    };

    grunt.initConfig({
        shell: {
            composerSelfUpdate: {
                command: 'composer self-update'
            },
            composerUpdate: {
                command: 'composer update --prefer-dist'
            },
            createDb: {
                command: function(database, environment) {
                    return commandDb('php artisan migrate ', database, environment);
                }
            },
            dropDb: {
                command: function(database, environment) {
                    return commandDb('php artisan migrate:reset ', database, environment);
                }
            },
            refreshDb: {
                command: function(database, environment) {
                    return commandDb('php artisan migrate:refresh ', database, environment);
                }
            },
            serverStart: {
                command: 'php artisan serve'
            }
        },
        open: {
            serverStarted: {
                path: 'localhost:8000',
                app: 'Firefox'
            }
        },
        phpunit: {
            classes: {
                dir: '',
            },
            options: {
                bin: 'vendor/bin/phpunit'
            }
        },
        clean: {
            cleanLogs: {
                build: {
                    src: ["app/storage/logs/*", "!app/storage/logs/.gitignore"]
                }
            }
        },
        watch: {
            composer: {
                files: ['composer.json'],
                tasks: ['shell:composerUpdate']
            },
            migrations: {
                files: ['app/database/migrations/*.php', 'app/database/seeds/*.php'],
                tasks: ['db-refresh-testing', 'db-refresh-local']
            },
            tests: {
                files: [
                    'app/*.php',
                    'app/config/**/*,php/',
                    'app/controllers/*.php',
                    'app/lib/**/*.php',
                    'app/models/**/*.php',
                    'app/tests/**/*Test.php',
                    'phpunit.xml',
                    'phpunit.xml.dist'
                ],
                tasks: ['phpunit']
            }
        }
    });

    grunt.registerTask('db-create-testing', ['shell:createDb:dump:testing']);
    grunt.registerTask('db-create-local', ['shell:createDb']);
    grunt.registerTask('db-drop-testing', ['shell:dropDb:dump:testing']);
    grunt.registerTask('db-drop-local', ['shell:dropDb']);
    grunt.registerTask('db-refresh-testing', ['shell:refreshDb:dump:testing']);
    grunt.registerTask('db-refresh-local', ['shell:refreshDb']);
    grunt.registerTask('server-start', ['open:serverStarted', 'shell:serverStart']);
    grunt.registerTask('laravel-start', ['shell:composerSelfUpdate', 'db-refresh-testing', 'db-refresh-local', 'server-start']);
    grunt.registerTask('cleanlogs', ['clean:cleanLogs']);
    grunt.registerTask('default', ['cleanlogs', 'db-refresh-testing', 'phpunit', 'watch']);

};
