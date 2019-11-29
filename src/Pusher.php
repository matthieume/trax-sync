<?php

namespace Trax\Sync;

use Trax\Sync\Models\Push;
use XapiServer;

class Pusher
{
    /**
     * Push the statements.
     *
     * @param string $connection_name
     * @param bool $all
     * @return string
     */
    public function push(string $connection_name, bool $all = false)
    {
        // Instanciate and check the connection.
        $store = XapiServer::statements();
        $connector = new Connection($connection_name);
        $connector->check();

        // Remove logs to send all.
        if ($all) {
            Push::where('connector', $connector->id())->delete();
        }

        // Before running.
        $passed = 0;
        $failed = 0;

        // Repeat for each batch.
        while (true) {

            // Get failed attempts to retry.
            $retry_logs = Push::where('connector', $connector->id())->where('error', '<>', 0)->where('attempts', '<', $connector->maxAttempts())
                ->orderBy('statement_id')->take($connector->batchSize())->get();

            $retry_ids = $retry_logs->pluck('statement_id')->all();

            // $retry = Statement::whereIn('id', $retry_ids)->orderBy('id')->get();
            $retry = $store->get([
                'order-by' => 'id',
                'search' => [
                    'id' => (object)['operator' => 'IN', 'value' => $retry_ids]
                ]
            ]);
            
            // If we can add more statements.
            $more = $connector->batchSize() - $retry->count();
            if ($more > 0) {

                // Get statements to send for the first time.
                $last = Push::where('connector', $connector->id())->latest('id')->first();
                if ($last) {
                    // $new = Statement::where('id', '>', $last->statement_id)->orderBy('id')->take($more)->get();
                    $new = $store->get([
                        'order-by' => 'id',
                        'limit' => $more,
                        'search' => [
                            'id' => (object)['operator' => '>', 'value' => $last->statement_id]
                        ]
                    ]);
                } else {
                    //$new = Statement::orderBy('id')->take($more)->get();
                    $new = $store->get([
                        'order-by' => 'id',
                        'limit' => $more
                    ]);
                }
                $batch = $retry->concat($new)->all();

            } else {
                $batch = $retry->all();
            }

            // Break the loop.
            if (empty($batch)) {
                break;
            }

            // Extract statements.
            $input = [];
            foreach ($batch as $record) {
                $input[$record->id] = $record->data;
            }

            // Send the batch.
            $errors = $connector->push($input);

            // Update retry logs.
            $logs = $retry_logs->all();
            foreach ($logs as $log) {
                
                $log->attempts++;
                $log->error = $errors[$log->statement_id];
                $log->save();

                if ($log->error) {
                    $failed++;
                } else {
                    $passed++;
                }
            }

            // Add new logs.
            $statements = $new->all();
            foreach ($statements as $statement) {

                $log = new Push;
                $log->statement_id = $statement->id;
                $log->connector = $connector->id();
                $log->error = $errors[$statement->id];
                $log->attempts = 1;
                $log->save();

                if ($log->error) {
                    $failed++;
                } else {
                    $passed++;
                }
            }
        }

        // Feedback.
        $total = $passed + $failed;
        return "$total statements (re)sent: $passed passed, $failed failed";
    }

}
