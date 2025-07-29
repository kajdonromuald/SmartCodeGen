package test;

import model.Language;
import model.UserRequest;
import org.junit.jupiter.api.Test;
import service.CodeGenService;

import static org.junit.jupiter.api.Assertions.*;

public class CodeGenServiceTest {

    @Test
    public void testGenerateCodeNotEmpty() {
        CodeGenService service = new CodeGenService();
        UserRequest request = new UserRequest();
        request.setPrompt("Hello from test");
        request.setLanguage(Language.JAVA);
        String result = service.generateCode(request);
        assertNotNull(result);
        assertFalse(result.isEmpty());
    }
}
