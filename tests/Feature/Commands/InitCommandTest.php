<?php

namespace Tests\Feature\Commands;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(\Blueprint\Commands\InitCommand::class)]
final class InitCommandTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_creates_a_draft_file_from_stub_if_none_exists(): void
    {
        $this->filesystem->shouldReceive('exists')
            ->with('draft.yaml')
            ->andReturnFalse();
        $this->filesystem->shouldReceive('stub')
            ->with('draft.stub')
            ->andReturn('stub');
        $this->filesystem->shouldReceive('put')
            ->with('draft.yaml', 'stub');

        $this->filesystem->shouldReceive('exists')->with('app');

        $this->artisan('blueprint:init')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_does_not_create_a_draft_file_if_one_exists_already(): void
    {
        $this->filesystem->shouldReceive('exists')
            ->with('draft.yaml')
            ->andReturnTrue();
        $this->filesystem->shouldNotReceive('put');
        $this->filesystem->shouldReceive('exists')
            ->with('app');

        $this->artisan('blueprint:init')
            ->assertExitCode(0);
    }
}
