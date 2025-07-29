package ai.generators;

public class JavaScriptCodeGenerator implements CodeGenerator {

    @Override
    public String generate(String prompt) {
        return "function main() {\n" +
               "    console.log(\"" + prompt + "\");\n" +
               "}\n\n" +
               "main();";
    }
}
