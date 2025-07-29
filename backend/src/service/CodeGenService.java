package service;

import model.UserRequest;
import org.springframework.stereotype.Service;

@Service
public class CodeGenService {

    public String generateCode(UserRequest request) {
        // Egyszerű válasz a kérés alapján
        return "Kód generálva a következő nyelvhez: " + request.getLanguage() +
               " a következő prompt alapján: " + request.getPrompt();
    }
}
