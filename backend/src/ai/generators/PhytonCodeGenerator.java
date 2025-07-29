package ai.generators;

public class PythonCodeGenerator implements CodeGenerator {

    @Override
    public String generate(String prompt) {
        return "def main():\n" +
               "    print(\"" + prompt + "\")\n\n" +
               "if __name__ == '__main__':\n" +
               "    main()";
    }
}
