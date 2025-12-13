<?php
// app/Support/GatewayResponse.php
class GatewayResponse
{
    public static function success(string $service, string $action, $data, int $status = 200)
    {
        return response()->json([
            'success' => true,
            'service' => $service,
            'action'  => $action,
            'data'    => $data,
            'error'   => null,
            'meta'    => [
                'status'    => $status,
                'trace_id'  => request()->header('X-Request-Id') ?? (string) \Str::uuid(),
                'latency_ms'=> null,
            ],
        ], $status);
    }

    public static function error(string $service, string $action, string $code, string $message, int $status = 500, $details = null)
    {
        return response()->json([
            'success' => false,
            'service' => $service,
            'action'  => $action,
            'data'    => null,
            'error'   => [
                'code'        => $code,
                'http_status' => $status,
                'message'     => $message,
                'details'     => $details,
            ],
            'meta'    => [
                'status'    => $status,
                'trace_id'  => request()->header('X-Request-Id') ?? (string) \Str::uuid(),
                'latency_ms'=> null,
            ],
        ], $status);
    }
}
