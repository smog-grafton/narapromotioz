<?php

namespace App\Console\Commands;

use App\Services\WebSocketService;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;

class StartWebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:start {--port=8080} {--host=0.0.0.0} {--ssl}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the WebSocket server for live stream chat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $port = $this->option('port');
        $host = $this->option('host');
        $useSSL = $this->option('ssl');

        $this->info("Starting WebSocket server on {$host}:{$port}" . ($useSSL ? ' with SSL' : ''));

        // Create the event loop
        $loop = Factory::create();

        // Create the socket server
        $socket = new Server("{$host}:{$port}", $loop);

        // If SSL is enabled, wrap the server in a secure server
        if ($useSSL) {
            $sslOptions = [
                'local_cert' => env('SSL_CERT_PATH'),
                'local_pk' => env('SSL_KEY_PATH'),
                'verify_peer' => false,
                'allow_self_signed' => true,
            ];

            $socket = new SecureServer($socket, $loop, $sslOptions);
            $this->info('SSL is enabled. Make sure SSL_CERT_PATH and SSL_KEY_PATH environment variables are set.');
        }

        // Create the WebSocket server
        $webSocket = new WsServer(new WebSocketService());

        // Wrap in HTTP server to handle WebSocket handshake
        $server = new HttpServer($webSocket);

        // Create the IO server
        $ioServer = new IoServer($server, $socket, $loop);

        $this->info('WebSocket server started. Listening for connections...');

        // Run the server
        $ioServer->run();
    }
}