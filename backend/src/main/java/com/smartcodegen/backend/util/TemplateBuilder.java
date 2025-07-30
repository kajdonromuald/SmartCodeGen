package com.smartcodegen.backend.util;

import org.springframework.stereotype.Component;
import java.io.IOException;
import java.nio.charset.StandardCharsets;
import org.springframework.core.io.Resource;
import org.springframework.core.io.ResourceLoader;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.util.StreamUtils;

@Component
public class TemplateBuilder {

    @Autowired
    private ResourceLoader resourceLoader;

    public String getTemplate(String language, String templateName) {
        String templateContent = ""; // Alapértelmezett üres string, ha valami elromlik
        try {
            String resourcePath = "classpath:templates/" + language.toLowerCase() + "/" + templateName;
            System.out.println("DEBUG: TemplateBuilder: Próbálom betölteni a sablont: " + resourcePath); 

            Resource resource = resourceLoader.getResource(resourcePath);

            if (!resource.exists()) {
                System.err.println("HIBA: TemplateBuilder: A sablonfájl NEM TALÁLHATÓ: " + resource.getDescription());
                return "SABLON HIBA: Fájl nem található: " + resource.getDescription();
            }
            if (!resource.isReadable()) {
                System.err.println("HIBA: TemplateBuilder: A sablonfájl NEM OLVASHATÓ: " + resource.getDescription());
                return "SABLON HIBA: Fájl nem olvasható: " + resource.getDescription();
            }

            // --- EZ A RÉSZ KRITIKUS DEBUGGOLÁSHOZ ---
            try {
                // Csak akkor próbáljuk meg lekérni a fájlt, ha az erőforrás támogatja (pl. nem JAR-on belül van)
                if (resource.isFile()) { 
                    System.out.println("DEBUG: TemplateBuilder: Fájl abszolút útvonala: " + resource.getFile().getAbsolutePath());
                } else {
                    System.out.println("DEBUG: TemplateBuilder: Erőforrás nem fizikai fájl (pl. JAR-on belül van).");
                }
            } catch (Exception fileEx) {
                System.err.println("FIGYELEM: Nem sikerült lekérni a fájl abszolút útvonalát (ez normális lehet JAR-ban): " + fileEx.getMessage());
            }
            // ------------------------------------------

            templateContent = StreamUtils.copyToString(resource.getInputStream(), StandardCharsets.UTF_8);
            System.out.println("DEBUG: TemplateBuilder: Sablon sikeresen beolvasva! Hossza: " + templateContent.length() + " karakter."); 

            // --- EZ IS KRITIKUS! ---
            System.out.println("DEBUG: TemplateBuilder: Beolvasott tartalom:\n---START TEMPLATE---\n" + templateContent + "\n---END TEMPLATE---");
            // -----------------------

            return templateContent;

        } catch (IOException e) {
            System.err.println("HIBA: TemplateBuilder: IOException a sablon beolvasásánál: " + e.getMessage());
            e.printStackTrace();
            return "SABLON HIBA: Olvasási probléma: " + e.getMessage();
        } catch (Exception e) {
            System.err.println("HIBA: TemplateBuilder: Általános hiba a sablon beolvasásánál: " + e.getMessage());
            e.printStackTrace();
            return "SABLON HIBA: Általános probléma: " + e.getMessage();
        }
    }
}