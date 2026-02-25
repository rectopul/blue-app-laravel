<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return view('app.main.team.manager', compact('token'));
    }
    /**
     * Obter lista de usuários indicados
     */
    public function getReferrals()
    {
        $user = Auth::user();

        $referrals = User::where('ref_by', $user->ref_id)
            ->select('id', 'name', 'realname', 'email', 'username', 'created_at', 'active_member', 'investor')
            ->withCount(['investments' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['investments' => function ($query) {
                $query->where('status', 'completed');
            }], 'amount')
            ->withSum('commissions', 'amount')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'referrals' => $referrals,
                'total_count' => $referrals->count(),
                'active_count' => $referrals->where('active_member', 1)->count(),
                'investor_count' => $referrals->where('investor', 1)->count(),
            ]
        ]);
    }

    /**
     * Obter estatísticas de comissões
     */
    public function getCommissionStats()
    {
        $user = Auth::user();

        // Suponho que tenha uma tabela de comissões relacionada
        $commissions = $user->commissions()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $totalCommission = $user->commissions()->sum('amount');
        $pendingCommission = $user->commissions()->where('status', 'pending')->sum('amount');
        $paidCommission = $user->commissions()->where('status', 'paid')->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_commission' => $totalCommission,
                'pending_commission' => $pendingCommission,
                'paid_commission' => $paidCommission,
                'monthly_data' => $commissions,
            ]
        ]);
    }

    /**
     * Gerar novo link de referência
     */
    public function generateReferralLink()
    {
        $user = Auth::user();

        if (empty($user->ref_id)) {
            $ref_id = strtoupper(substr(md5(time() . $user->id), 0, 8));

            $user->ref_id = $ref_id;
            $user->save();
        }

        $referralLink = url('/register?ref=' . $user->ref_id);

        return response()->json([
            'success' => true,
            'data' => [
                'ref_id' => $user->ref_id,
                'referral_link' => $referralLink
            ]
        ]);
    }
}
