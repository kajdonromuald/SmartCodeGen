package test.generators;

import ai.generators.JavaCodeGenerator;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

public class JavaCodeGeneratorTest {

    @Test
    public void testGenerateNotEmpty() {
        JavaCodeGenerator generator = new JavaCodeGenerator();
        String result = generator.generate("Hello Java");
        assertNotNull(result);
        assertFalse(result.isEmpty());
    }
}
