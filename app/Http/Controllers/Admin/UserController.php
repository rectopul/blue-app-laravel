<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserLedger;

class UserController extends Controller
{
    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed',
            ], [
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 6 characters',
                'password.confirmed' => 'Password confirmation does not match',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = User::findOrFail($id);

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully for user: ' . $user->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user balance (add or subtract)
     */
    public function updateBalance(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:0.01',
                'operation' => 'required|in:add,subtract',
                'reason' => 'required|string|max:255',
            ], [
                'amount.required' => 'Amount is required',
                'amount.numeric' => 'Amount must be a valid number',
                'amount.min' => 'Amount must be at least 0.01',
                'operation.required' => 'Operation type is required',
                'operation.in' => 'Operation must be either add or subtract',
                'reason.required' => 'Reason is required',
                'reason.max' => 'Reason cannot exceed 255 characters',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = User::findOrFail($id);
            $amount = $request->amount;
            $operation = $request->operation;
            $reason = $request->reason;

            $previousBalance = $user->balance;

            DB::beginTransaction();

            if ($operation === 'add') {
                $newBalance = $previousBalance + $amount;
                $ledgerAmount = $amount;
            } else {
                // Check if user has sufficient balance for subtraction
                if ($previousBalance < $amount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient balance. Current balance: R$ ' . number_format($previousBalance, 2)
                    ], 422);
                }
                $newBalance = $previousBalance - $amount;
                $ledgerAmount = -$amount;
            }

            // Update user balance
            $user->update(['balance' => $newBalance]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Balance updated successfully! New balance: R$ ' . number_format($newBalance, 2),
                'data' => [
                    'previous_balance' => $previousBalance,
                    'new_balance' => $newBalance,
                    'amount' => $amount,
                    'operation' => $operation
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating balance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gift bonus to user
     */
    public function giftBonus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bonus' => 'required|string|max:100',
            ], [
                'bonus.required' => 'Bonus code is required',
                'bonus.max' => 'Bonus code cannot exceed 100 characters',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = User::findOrFail($id);
            $bonusCode = $request->bonus;

            // Aqui você pode implementar sua lógica de bônus
            // Por exemplo, verificar se o código existe em uma tabela de bônus
            // e aplicar o valor correspondente

            // Exemplo simples - você deve adaptar conforme sua regra de negócio
            $bonusAmount = $this->calculateBonusAmount($bonusCode);

            if ($bonusAmount === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid bonus code: ' . $bonusCode
                ], 422);
            }

            $previousBalance = $user->balance;
            $newBalance = $previousBalance + $bonusAmount;

            DB::beginTransaction();

            // Update user balance
            $user->update(['balance' => $newBalance]);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bonus applied successfully! Bonus amount: R$ ' . number_format($bonusAmount, 2),
                'data' => [
                    'bonus_code' => $bonusCode,
                    'bonus_amount' => $bonusAmount,
                    'previous_balance' => $previousBalance,
                    'new_balance' => $newBalance
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error applying bonus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate bonus amount based on bonus code
     * Adapte esta função conforme suas regras de negócio
     */
    private function calculateBonusAmount($bonusCode)
    {
        // Exemplo simples - você deve implementar sua própria lógica
        $bonusCodes = [
            'WELCOME10' => 10.00,
            'BONUS20' => 20.00,
            'SPECIAL50' => 50.00,
            'VIP100' => 100.00,
        ];

        return isset($bonusCodes[$bonusCode]) ? $bonusCodes[$bonusCode] : false;

        // Alternativa: buscar em uma tabela de bônus
        // $bonus = BonusCode::where('code', $bonusCode)->where('active', true)->first();
        // return $bonus ? $bonus->amount : false;
    }

    /**
     * Ban user
     */
    public function banUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['ban_unban' => 'ban']);

            return redirect()->back()->with('success', 'User banned successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error banning user: ' . $e->getMessage());
        }
    }

    /**
     * Unban user
     */
    public function unbanUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['ban_unban' => 'unban']);

            return redirect()->back()->with('success', 'User unbanned successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error unbanning user: ' . $e->getMessage());
        }
    }
}
