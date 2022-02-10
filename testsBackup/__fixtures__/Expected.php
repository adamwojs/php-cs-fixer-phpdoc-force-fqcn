<?php

use CustomNamespace\TestClass;

class Original {

    /**
     * @param  \CustomNamespace\TestClass|(\CustomNamespace\TestClass&\CustomNamespace\TestClass<\CustomNamespace\TestClass, \CustomNamespace\TestClass>)|\CustomNamespace\TestClass{string: int} $type
     * @return \CustomNamespace\TestClass|(\CustomNamespace\TestClass&\CustomNamespace\TestClass<\CustomNamespace\TestClass, \CustomNamespace\TestClass>)|\CustomNamespace\TestClass{string: int}
     */
    public function original($type) {
        return $type;
    }

}