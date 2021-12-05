<?php

namespace App\Repositories;

use App\Models\Song;
use App\Repositories\Traits\Searchable;
use App\Services\HelperService;
use Illuminate\Support\Collection;

class SongRepository extends AbstractRepository
{
    use Searchable;

    private HelperService $helperService;

    public function __construct(HelperService $helperService)
    {
        parent::__construct();

        $this->helperService = $helperService;
    }

    public function getOneByPath(string $path): ?Song
    {
        return $this->getOneById($this->helperService->getFileHash($path));
    }

    /** @return Collection|array<Song> */
    public function getAllHostedOnS3(): Collection
    {
        return Song::hostedOnS3()->get();
    }
}
