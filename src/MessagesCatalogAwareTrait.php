<?php

namespace Elixir\STDLib;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait MessagesCatalogAwareTrait
{
    /**
     * @var MessagesCatalog
     */
    protected $messagesCatalog;
    
    /**
     * @param MessagesCatalog $value
     */
    public function setMessagesCatalog(MessagesCatalog $value)
    {
        $this->messagesCatalog = clone $value;

        foreach ($this->getDefaultCatalogMessages() as $key => $value) {
            if (!$this->messagesCatalog->has($key)) {
                $this->messagesCatalog->set($key, $value);
            }
        }
    }

    /**
     * @return MessagesCatalog
     */
    public function getMessagesCatalog()
    {
        return $this->messagesCatalog;
    }

    /**
     * @return array
     */
    abstract protected function getDefaultCatalogMessages();
}
