PerformerRerUserBundle
======================

Configurazione
--------------

Aggiungere in ``AppKernel.php``:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Performer\RerUserBundle\PerformerRerUserBundle(),
    );
}
```

Creare entity:
```php
<?php
// src/AppBundle/Entity/Utente.php

use Doctrine\ORM\Mapping as ORM;
use Performer\RerUserBundle\Entity\User;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Performer\RerUserBundle\Repository\UserRepository")
 */
class Utente extends User
{
}
```

Aggiungere in configurazione:
```yaml
# app/config/config.yml

performer_rer_user:
    user_class: AppBundle\Entity\Utente
    soap:
        # questi due valori vanno personalizzati
        app_id: app_id_fornito_da_RER
        salt: salt_fornito_da_RER
```