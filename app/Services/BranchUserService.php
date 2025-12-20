<?php

namespace App\Services;

use App\Exceptions\BranchUserException;
use App\Repositories\Contracts\UserRepositoryInterface;

class BranchUserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Отписывает пользователей от филиала
     *
     * @param array $userIds IDs пользователей
     * @param int $branchId ID филиала
     * @return int Количество отписанных пользователей
     * @throws BranchUserException
     */
    public function unsubscribeUsers(array $userIds, int $branchId): int
    {
        // Проверяем, что IDs не пустые
        if (empty($userIds)) {
            throw new BranchUserException('Не выбраны пользователи для отписки');
        }

        // Получаем IDs пользователей, реально принадлежащих филиалу
        $validUserIds = $this->userRepository->getUserIdsInBranch($userIds, $branchId);

        if (empty($validUserIds)) {
            throw new BranchUserException(
                'Ни один из выбранных пользователей не принадлежит этому филиалу'
            );
        }

        // Отписываем
        return $this->userRepository->unsubscribeFromBranch($validUserIds);
    }
}