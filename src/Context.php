<?php


namespace statemm;

use Illuminate\Database\Eloquent\Model;

class Context
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var State
     */
    private $state;

    /**
     * @var array
     */
    private $logs = [];

    public function __construct(hasState $model)
    {
        $this->model = $model;
        // TODO: Implement initial state class.
        // your start state must be initialized.
        $this->state =  $model->initialState();
        $this->proceed();
    }

    /**
     * set your Model to returned
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }


    public function proceed(): State
    {
        $this->state->setContext($this);
        $this->state->proceed();
        return $this->state;
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addToLogs(string $log)
    {
        $this->logs[] = $log;
    }

    /**
     * @param State $state
     */
    public function setState(State $state): void
    {
        $this->state = $state;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }
}
