<?php

namespace TTBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TTBundle extends Bundle
{
    public function boot() {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $conn = $em->getConnection();
        $conn->getConfiguration()->setFilterSchemaAssetsExpression("~^(?!global_restaurantslos)~");
    }
}
