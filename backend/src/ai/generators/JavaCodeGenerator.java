package ai.generators;

public class JavaCodeGenerator implements CodeGenerator {

    @Override
    public String generate(String prompt) {
        return "public class HelloWorld {\n" +
               "    public static void main(String[] args) {\n" +
               "        System.out.println(\"" + prompt + "\");\n" +
               "    }\n" +
               "}";
    }
}
