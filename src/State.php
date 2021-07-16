<?php


namespace statemm;


use Illuminate\Database\Eloquent\Model;

abstract class State
{
    protected $state;

    private $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
        $this->addStateToLog();
    }

    protected function transitionTo(State $state)
    {
        $this->getContext()->setState($state);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }


    public function getModel(): Model
    {
        return $this->getContext()->getModel();
    }

    private function addStateToLog()
    {
        $this->getContext()->addToLogs($this->state);
    }

    abstract public function proceed();

}
