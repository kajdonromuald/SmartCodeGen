package com.smartcodegen.backend.ai;

import com.smartcodegen.backend.ai.generators.CodeGenerator;
import com.smartcodegen.backend.ai.generators.JavaCodeGenerator;
import com.smartcodegen.backend.ai.generators.JavaScriptCodeGenerator;
import com.smartcodegen.backend.ai.generators.PythonCodeGenerator;
import com.smartcodegen.backend.model.Language;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import java.util.HashMap;
import java.util.Map;
import jakarta.annotation.PostConstruct;

@Component
public class AIEngine {

    private final Map<Language, CodeGenerator> generators = new HashMap<>();

    @Autowired
    private JavaCodeGenerator javaCodeGenerator;
    @Autowired
    private PythonCodeGenerator pythonCodeGenerator;
    @Autowired
    private JavaScriptCodeGenerator javaScriptCodeGenerator;

    @PostConstruct
    public void init() {
        generators.put(Language.JAVA, javaCodeGenerator);
        generators.put(Language.PYTHON, pythonCodeGenerator);
        generators.put(Language.JAVASCRIPT, javaCodeGenerator); // Ez is javítva legyen, ha elírás volt: javaScriptCodeGenerator
    }

    public String generateCode(String prompt, Language language) {
        // ADD HOZZÁ EZEKET A DEBUG LOGOKAT:
        System.out.println("DEBUG: AIEngine: generateCode hívva. Nyelv: " + language + ", Prompt: " + prompt);
        
        CodeGenerator generator = generators.get(language);
        if (generator == null) {
            System.err.println("HIBA: AIEngine: Nem található generátor a nyelvhez: " + language);
            return "Nem támogatott nyelv: " + language;
        }
        
        String generatedOutput = generator.generateCode(prompt);
        System.out.println("DEBUG: AIEngine: Generátor válasza (hossz): " + generatedOutput.length());
        return generatedOutput;
    }
}