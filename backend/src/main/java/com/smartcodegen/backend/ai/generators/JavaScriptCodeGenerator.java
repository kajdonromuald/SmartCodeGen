package com.smartcodegen.backend.ai.generators;

import com.smartcodegen.backend.util.TemplateBuilder;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
public class JavaScriptCodeGenerator implements CodeGenerator {

    @Autowired
    private TemplateBuilder templateBuilder;

    @Override
    public String generateCode(String prompt) { // Itt generateCode
        // Ellenőrizd, hogy a resources/templates/js/module_template.txt létezik és van benne tartalom!
        String template = templateBuilder.getTemplate("js", "module_template.txt");
        return "Generated JavaScript code based on prompt: '" + prompt + "'\n" + template;
    }
}