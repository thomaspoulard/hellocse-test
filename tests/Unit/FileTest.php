<?php

namespace Tests\Unit;

use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;
use Mockery;

class FileTest extends TestCase
{
    protected FileService $fileService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileService = new FileService();
    }

    /**
     * Test to create a filename with a uuid.
     */
    public function test_create_file_name_with_uuid()
    {
        $fileMock = Mockery::mock(UploadedFile::class);
        $fileMock->shouldReceive('getClientOriginalExtension')
            ->once()
            ->andReturn('jpg');

        $fileService = Mockery::mock(FileService::class)->makePartial();

        $expectedUuid = 'mock-uuid';
        $fileService->shouldReceive('generateUuid')
            ->once()
            ->andReturn($expectedUuid);

        $fileName = $fileService->uniqueMediaName($fileMock);

        $this->assertEquals($expectedUuid . '.' . 'jpg', $fileName);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
