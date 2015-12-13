<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Http\Controllers\AdminController;
use \Mail;

class CronUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'CronUpdate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the scores, bets, games time. Run this during the night.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        try
        {
            $this->info('Starting update...');
            $a = new AdminController();
            $nb = $a->getRefresh('cron');
            $this->info($nb[0].'/'.$nb[1]);
            $this->info('Update done. Site should be back up !');
            Mail::send('emails.cron', array('done' => $nb[0], 'total' => $nb[1], 'dnb' => $nb[2]), function($message)
            {
                $message->to('clcsblack@gmail.com', 'Nightly Cron')->cc('isaac.hibou@gmail.com')->subject('[BETS] Cron Update Result!');
            });
        }
        catch(\Exception $e){
            Mail::send('emails.cronfail', array('error' => self::getExceptionTraceAsString($e)), function($message)
            {
                $message->to('clcsblack@gmail.com', 'Nightly Cron')->cc('isaac.hibou@gmail.com')->subject('[BETS] Cron Failed');
            });
            \Artisan::call('up');
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

    private function getExceptionTraceAsString($exception) {
        $rtn = "";
        $count = 0;
        foreach ($exception->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $rtn .= @sprintf( "#%s %s(%s): %s(%s)\n",
                $count,
                $frame['file'],
                $frame['line'],
                $frame['function'],
                $args );
            $count++;
        }
        return $rtn;
    }
}
