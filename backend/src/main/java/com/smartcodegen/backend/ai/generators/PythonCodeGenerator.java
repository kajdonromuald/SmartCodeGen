package com.smartcodegen.backend.ai.generators;

import com.smartcodegen.backend.util.TemplateBuilder;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
public class PythonCodeGenerator implements CodeGenerator {

    @Autowired
    private TemplateBuilder templateBuilder;

    @Override
    public String generateCode(String prompt) { // Itt generateCode
        // Ellenőrizd, hogy a resources/templates/python/function_template.txt létezik és van benne tartalom!
        String template = templateBuilder.getTemplate("python", "function_template.txt");
        return "Generated Python code based on prompt: '" + prompt + "'\n" + template;
    }
}