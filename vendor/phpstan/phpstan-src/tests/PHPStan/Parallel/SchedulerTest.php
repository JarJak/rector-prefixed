<?php

declare (strict_types=1);
namespace PHPStan\Parallel;

use _PhpScoper006a73f0e455\PHPUnit\Framework\TestCase;
class SchedulerTest extends \_PhpScoper006a73f0e455\PHPUnit\Framework\TestCase
{
    public function dataSchedule() : array
    {
        return [[1, 16, 1, 50, 115, 1, [50, 50, 15]], [16, 16, 1, 30, 124, 5, [30, 30, 30, 30, 4]], [16, 3, 1, 30, 124, 3, [30, 30, 30, 30, 4]], [16, 16, 1, 10, 298, 16, [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 8]], [16, 16, 2, 30, 124, 2, [30, 30, 30, 30, 4]], [16, 16, 2, 20, 1, 1, [1]]];
    }
    /**
     * @dataProvider dataSchedule
     * @param int $cpuCores
     * @param int $maximumNumberOfProcesses
     * @param int $minimumNumberOfJobsPerProcess
     * @param int $jobSize
     * @param int $numberOfFiles
     * @param int $expectedNumberOfProcesses
     * @param array<int> $expectedJobSizes
     */
    public function testSchedule(int $cpuCores, int $maximumNumberOfProcesses, int $minimumNumberOfJobsPerProcess, int $jobSize, int $numberOfFiles, int $expectedNumberOfProcesses, array $expectedJobSizes) : void
    {
        $files = \array_fill(0, $numberOfFiles, 'file.php');
        $scheduler = new \PHPStan\Parallel\Scheduler($jobSize, $maximumNumberOfProcesses, $minimumNumberOfJobsPerProcess);
        $schedule = $scheduler->scheduleWork($cpuCores, $files);
        $this->assertSame($expectedNumberOfProcesses, $schedule->getNumberOfProcesses());
        $jobSizes = \array_map(static function (array $job) : int {
            return \count($job);
        }, $schedule->getJobs());
        $this->assertSame($expectedJobSizes, $jobSizes);
    }
}