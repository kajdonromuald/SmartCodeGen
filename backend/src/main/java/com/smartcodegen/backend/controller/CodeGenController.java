package com.smartcodegen.backend.controller;

import com.smartcodegen.backend.model.UserRequest;
import com.smartcodegen.backend.service.CodeGenService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api")
@CrossOrigin(origins = "http://localhost")
public class CodeGenController {

    @Autowired
    private CodeGenService codeGenService;

    @PostMapping("/generate")
    public ResponseEntity<String> generateCode(@RequestBody UserRequest request) {
        String generatedCode = codeGenService.generateCode(request);
        return ResponseEntity.ok(generatedCode);
    }
}