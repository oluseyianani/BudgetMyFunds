<?php

namespace App\Repositories\V1;

use Exception;
use App\Models\MonthlyIncome;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MonthlyIncomeRepository extends BaseRepository
{
    public function fetchMany(int $begin, int $perPage, string $sortBy, string $sortDirection, $year = null, $month = null, $userId = null)
    {
        $userId = auth()->user()->isSuperAdmin() ? $userId : auth()->user()['id'];
        $monthlyIncome;
        try {
            if ($year && $month) {
                $budgetData = MonthlyIncome::forAuthorizedUser($userId)->whereMonth('date_received', $month)
                                ->whereYear('date_received', $year)
                                ->offset($begin)
                                ->limit($perPage)
                                ->get();
            } else {
                $monthlyIncome = MonthlyIncome::forAuthorizedUser($userId)->orderBy($sortBy, $sortDirection)
                                ->offset($begin)
                                ->limit($perPage)
                                ->get();
            }

            return formatResponse(200, 'Ok', true, $monthlyIncome);
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $monthlyIncome = MonthlyIncome::create($data);

            return formatResponse(201, 'Monthly income tracking created', true, collect($monthlyIncome));
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    public function fetchOne(int $id)
    {
        try {
            $creatorId = auth()->user()['id'];
            $monthlyIncome = MonthlyIncome::forAuthorizedUser($creatorId)
                                ->findOrFail($id);

            return formatResponse(200, 'Ok', true, collect($monthlyIncome));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Montly income not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $monthlyIncome = $this->getMonthlyIncome($id);

            $monthlyIncome['beneficiary'] = $data['beneficiary'];
            $monthlyIncome['amount'] = $data['amount'];
            $monthly['date_received'] = $data['date_received'];

            if (isset($data['income_source'])) {
                $monthlyIncome['income_source'] = $data['income_source'];
            }

            if (!isset($data['income_source'])) {
                $monthlyIncome['income_source'] = null;
            }

            if ($monthlyIncome->isDirty()) {
                $monthlyIncome->save();

                return formatResponse(200, 'Monthly income updated', true, collect($monthlyIncome));
            }

            return formatResponse(200, 'No changes made. No update required.', true, collect($monthlyIncome));
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Montly income not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $monthlyIncome = $this->getMonthlyIncome($id);
            $monthlyIncome->delete();

            return formatResponse(200, 'Monthly income deleted', true);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return formatResponse(404, 'Montly income not found');
            }
            return formatResponse(fetchErrorCode($e), get_class($e) . ": ". $e->getMessage());
        }
    }

    public function getMonthlyIncome($id)
    {
        return MonthlyIncome::forAuthorizedUser(auth()->user()['id'])->findOrFail($id);
    }
}
