<?php

namespace App\Services\AsetLancar;

use App\Models\AsetLancar;
use App\Repositories\AsetLancarRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class AsetLancarService
{
    public function __construct(
        protected AsetLancarRepository $repository,
        protected AsetLancarCalculationService $calculationService
    ) {}

    /**
     * Get filtered and paginated AsetLancar list.
     */
    public function getFilteredList(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    /**
     * Create new AsetLancar with automatic calculations.
     */
    public function create(array $data): AsetLancar
    {
        $calculatedData = $this->calculationService->calculate($data);

        return $this->repository->create($calculatedData);
    }

    /**
     * Update existing AsetLancar with automatic calculations.
     */
    public function update(AsetLancar $asetLancar, array $data): bool
    {
        $calculatedData = $this->calculationService->calculate($data);

        return $this->repository->update($asetLancar, $calculatedData);
    }

    /**
     * Delete AsetLancar record.
     */
    public function delete(AsetLancar $asetLancar): bool
    {
        return $this->repository->delete($asetLancar);
    }

    /**
     * Find AsetLancar by ID.
     */
    public function find(int $id): ?AsetLancar
    {
        return $this->repository->findWithRelations($id);
    }
}
