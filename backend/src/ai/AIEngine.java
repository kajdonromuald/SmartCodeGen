
package ai;

import ai.generators.CodeGenerator;
import ai.generators.JavaCodeGenerator;
import ai.generators.PythonCodeGenerator;
import ai.generators.JavaScriptCodeGenerator;
import model.Language;

public class AIEngine {

    public static String generateResponse(Language language, String prompt) {
        CodeGenerator generator;

        switch (language) {
            case JAVA:
                generator = new JavaCodeGenerator();
                break;
            case PYTHON:
                generator = new PythonCodeGenerator();
                break;
            case JAVASCRIPT:
                generator = new JavaScriptCodeGenerator();
                break;
            default:
                return "// Unsupported language";
        }

        return generator.generate(prompt);
    }
}
