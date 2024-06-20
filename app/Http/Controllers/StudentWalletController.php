<?php

namespace App\Http\Controllers;
use App\Models\teacher_wallets;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StudentWalletController extends Controller
{
  

    public function transferMoneyToTeacher(Request $request, $studentId, $teacherId)
    {
        // Find the student by ID
        $student = Student::findOrFail($studentId);
    
        // Validate the request data (ensure the 'amount' field is numeric and non-negative)
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
    
        // Get the student's wallet or create one if it doesn't exist
        $studentWallet = $student->student_wallets ;
        if (!$studentWallet) {
            return response()->json(['error' => 'Student wallet not found'], 404);
        }
    
         // Check if the wallet balance is sufficient for the transfer
    if ($studentWallet->value < $validatedData['amount']) {
        return response()->json(['error' => 'Insufficient funds'], 400);
    }
        $studentWallet->value -= $validatedData['amount']; // Withdraw from student's wallet
        $studentWallet->save();
    
        // Find the recipient (teacher) by ID
        $recipient = Teacher::find($teacherId);
    
        if (!$recipient) {
            // Handle the case where the recipient (teacher) is not found
            // (e.g., return an error response)
            return response()->json(['error' => 'Recipient not found'], 404);
        }
    
        // Deposit into recipient's wallet
        $recipientWallet = $recipient->teacher_wallets ;
        if (!$recipientWallet) {
            return response()->json(['error' => 'Recipient (teacher) does not have a wallet'], 400);
        }
        $recipientWallet->value += $validatedData['amount'];
        $recipient->teacher_wallets()->save($recipientWallet);
    
        return response()->json(['message' => 'Transfer successful'], 200);
    }  

    
    public function getStudentWalletValue($studentId)
{
    // Find the student by ID
    $student = Student::findOrFail($studentId);

    // Get the student's wallet value
    $walletValue = $student->student_wallets->value ?? 0;

    return response()->json(['wallet_value' => $walletValue], 200);
}

}
