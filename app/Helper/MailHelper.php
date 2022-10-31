<?php

namespace App\Helper;

use \Exception;
use Illuminate\Mail\Mailable;
use \Swift_TransportException;
use Illuminate\Http\JsonResponse;
use \Swift_RfcComplianceException;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Exception\RfcComplianceException;

class MailHelper
{
    public static function sendEmail(
        string $emailTo,
        Mailable $builtEmail,
        JsonResponse $successResponse = null
    ): JsonResponse {

        $errors = null;
        try {
            Mail::to($emailTo)->send($builtEmail);
            $message = 'Mail has been sent your account';
            return response()->json(['message' => $message]);
        } catch (Swift_RfcComplianceException $e) {
            $errors = ['email' => [$e->getMessage()]];
            return response()->json(['status' => false, 'errors' => $errors], HttpStatusCodes::CLIENT_ERROR_UNPROCESSABLE_ENTITY);
        } catch (Swift_TransportException $e) {
            $errors = ['email' => ['The contact message cannot be sent because of an error with the sendmail process.']];
            return response()->json(['status' => false, 'errors' => $errors]);
        } catch (Exception $e) {
            $errors = ['email' => ['The contact message cannot be sent because of an error sending the email.' . $e->getMessage()]];
            return response()->json(['status' => false, 'errors' => $errors]);
        }

        if ($successResponse) {
            return $successResponse;
        }

        return response()->json(['status' => true, 'errors' => $errors]);
    }
}
