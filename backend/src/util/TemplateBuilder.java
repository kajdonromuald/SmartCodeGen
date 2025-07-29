public static String buildTemplate(Language language, String prompt) {
    switch (language) {
        case JAVA:
            return "public class HelloWorld {\n    public static void main(String[] args) {\n        System.out.println(\"" + prompt + "\");\n    }\n}";
        case PYTHON:
            return "def main():\n    print(\"" + prompt + "\")\n\nif __name__ == '__main__':\n    main()";
        case JAVASCRIPT:
            return "function main() {\n    console.log(\"" + prompt + "\");\n}\n\nmain();";
        default:
            return "// Language not supported";
    }
}
