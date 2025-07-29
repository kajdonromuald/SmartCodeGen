package controller;

import model.UserRequest;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import service.CodeGenService;

@RestController
@RequestMapping("/api/codegen")
@CrossOrigin(origins = "*") // ha frontend más domainen fut
public class CodeGenController {

    private final CodeGenService codeGenService;

    @Autowired
    public CodeGenController(CodeGenService codeGenService) {
        this.codeGenService = codeGenService;
    }

    @PostMapping("/generate")
    public ResponseEntity<String> generateCode(@RequestBody UserRequest request) {
        String generatedCode = codeGenService.generateCode(request);
        return ResponseEntity.ok(generatedCode);
    }

    @GetMapping("/status")
    public ResponseEntity<String> getStatus() {
        return ResponseEntity.ok("CodeGen API működik 🚀");
    }
}
