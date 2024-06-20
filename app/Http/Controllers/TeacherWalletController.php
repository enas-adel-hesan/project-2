<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\teacher_wallets;

use Illuminate\Http\Request;

class TeacherWalletController extends Controller
{
    public function getStudentWalletValue($studentId)
    {
        // Find the student by ID
        $teacher = Teacher::findOrFail($studentId);
    
        // Get the student's wallet value
        $walletValue = $teacher->teacher_wallets->value ?? 0;
    
        return response()->json(['wallet_value' => $walletValue], 200);
    }

}
