<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Проверяет принадлежность пользователей филиалу и возвращает их IDs
     *
     * @param array $userIds
     * @param int $branchId
     * @return array
     */
    public function getUserIdsInBranch(array $userIds, int $branchId): array;

    /**
     * Отписывает пользователей от филиала (обнуляет branch_id)
     *
     * @param array $userIds
     * @return int Количество обновлённых записей
     */
    public function unsubscribeFromBranch(array $userIds): int;



    public function findWithBranchInfo(int $id): ?User;
    public function listWithBranchInfo(int $perPage = 20): LengthAwarePaginator;
    public function countAll(): int;
}