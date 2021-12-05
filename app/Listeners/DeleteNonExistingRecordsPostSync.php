<?php

namespace App\Listeners;

use App\Models\Song;
use App\Repositories\SongRepository;
use App\Services\HelperService;
use App\Values\SyncResult;

class DeleteNonExistingRecordsPostSync
{
    private SongRepository $songRepository;
    private HelperService $helper;

    public function __construct(SongRepository $songRepository, HelperService $helper)
    {
        $this->songRepository = $songRepository;
        $this->helper = $helper;
    }

    public function handle(SyncResult $result): void
    {
        $hashes = array_merge(
            $result->validEntries()->map(fn (string $path): string => $this->helper->getFileHash($path)),
            $this->songRepository->getAllHostedOnS3()->pluck('id')
        );

        Song::deleteWhereIDsNotIn($hashes);
    }
}
