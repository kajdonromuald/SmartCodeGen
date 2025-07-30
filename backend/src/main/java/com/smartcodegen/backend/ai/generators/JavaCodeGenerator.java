package com.smartcodegen.backend.ai.generators;

import com.smartcodegen.backend.util.TemplateBuilder;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
public class JavaCodeGenerator implements CodeGenerator {

    @Autowired
    private TemplateBuilder templateBuilder;

    @Override
    public String generateCode(String prompt) {
        String template = templateBuilder.getTemplate("java", "class_template.txt");

        // FONTOS: Győződj meg róla, hogy ez a VISSZATÉRŐ STRING PONTOSAN IGY NÉZ KI:
        return "Kód generálva a következő nyelvhez: JAVA a következő prompt alapján: '" + prompt + "'\n\n" +
               "--- Generált kód ---\n" + // EZ AZ ELVÁLASZTÓ SZÖVEG
               template + "\n" +          // EZ A LÉNYEG, ITT VAN A SABLON TARTALMA
               "--------------------";     // EZ IS EGY ELVÁLASZTÓ SZÖVEG
    }
}