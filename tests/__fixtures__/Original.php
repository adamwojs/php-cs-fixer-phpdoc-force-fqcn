<?php

use CustomNamespace\TestClass;

class Original {

    /**
     * @param  TestClass|(TestClass&TestClass<TestClass, TestClass>)|TestClass{string: int} $type
     * @return TestClass|(TestClass&TestClass<TestClass, TestClass>)|TestClass{string: int}
     */
    public function original($type) {
        return $type;
    }

}