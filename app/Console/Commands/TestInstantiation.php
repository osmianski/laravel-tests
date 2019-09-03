<?php

namespace App\Console\Commands;

use App\DependentService;
use App\SampleService;
use Illuminate\Console\Command;

class TestInstantiation extends Command
{
    protected $signature = 'test:instantiation';
    protected $description = 'Tests performance of creating objects';
    protected $count = 1000;
    protected $sample_service;
    protected $dependent_service;

    public function getSampleService() {
        if (!$this->sample_service) {
            $this->sample_service = new SampleService();
        }

        return $this->sample_service;
    }

    public function getDependentService() {
        if (!$this->dependent_service) {
            $this->dependent_service = new DependentService($this->getSampleService(), $this->getSampleService());
        }

        return $this->dependent_service;
    }

    public function handle()
    {
        $this->measure('Automatic resolution from DI container (0 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = app(SampleService::class);
            }
        });

        $this->measure('Automatic resolution from DI container (2 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = app(DependentService::class);
            }
        });

        $this->measure('bind() (0 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = app('bound_sample_service');
            }
        });

        $this->measure('bind() (2 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = app('bound_dependent_service');
            }
        });

        $this->measure('singleton() (0 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = app('singleton_sample_service');
            }
        });

        $this->measure('singleton() (2 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = app('singleton_dependent_service');
            }
        });

        $this->measure('new (0 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = new SampleService();
            }
        });

        $this->measure('new (2 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = new DependentService(new SampleService(), new SampleService());
            }
        });

        $this->measure('singleton property (0 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = $this->getSampleService();
            }
        });

        $this->measure('singleton property (2 dependencies)', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = $this->getDependentService();
            }
        });

        $this->measure('object', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = new \stdClass();
                $object->host = 'localhost';
                $object->port = 80;
            }
        });

        $this->measure('array', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $array = ['host' => 'localhost', 'port' => 80];
            }
        });

        $this->measure('object converted from array', function() {
            for ($i = 0; $i < $this->count; $i++) {
                $object = (object)['host' => 'localhost', 'port' => 80];
            }
        });
    }

    protected function measure($title, callable $callback) {
        $startedAt = microtime(true);
        $callback();
        $elapsed = sprintf("%.2f", (microtime(true) - $startedAt) * 1000);
        $this->output->writeln(__(':title: executed :count times in :elapsed ms',
            ['title' => $title, 'count' => $this->count, 'elapsed' => $elapsed]));
    }

}
