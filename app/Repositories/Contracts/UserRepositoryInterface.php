<?php

namespace App\Repositories\Contracts;

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
}