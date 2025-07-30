package com.smartcodegen.backend; // Ez a package a fájl helyéhez igazodik

import com.smartcodegen.backend.model.Language;      // Helyes import
import com.smartcodegen.backend.model.UserRequest;   // Helyes import
import com.smartcodegen.backend.service.CodeGenService; // Helyes import

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

public class CodeGenServiceTest {

    @Test
    public void testGenerateCodeNotEmpty() {
        // Itt valószínűleg szükség lesz a CodeGenService példányosítására
        // Spring Boot tesztekben ez általában @Autowired vagy @Inject-tel történik.
        // Egyelőre hagyjuk így, de ha Spring környezetben futtatod a tesztet,
        // akkor lehet, hogy ez a direkt példányosítás (`new CodeGenService()`) nem elég.
        // Viszont a fordítási hibákhoz most ez nem releváns.

        CodeGenService service = new CodeGenService();
        UserRequest request = new UserRequest();
        request.setPrompt("Hello from test");
        request.setLanguage(Language.JAVA);
        String result = service.generateCode(request);
        assertNotNull(result);
        assertFalse(result.isEmpty());
    }
}