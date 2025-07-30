package com.smartcodegen.backend.generators;

import com.smartcodegen.backend.ai.generators.JavaCodeGenerator;
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.assertNotNull;
import static org.junit.jupiter.api.Assertions.assertFalse;

public class JavaCodeGeneratorTest {

    @Test
    public void testGenerateNotEmpty() {

        JavaCodeGenerator generator = new JavaCodeGenerator(); 

        String result = generator.generateCode("Hello Java"); 
        assertFalse(result.isEmpty());
    }
}