# CRUD-de-Produtos - Universo Literário

Este projeto consiste em um Sistema de Gestão de Produtos, desenvolvido com técnicas de Orientação a Objetos e foco em armazenamento e autenticação de dados. O sistema permite a gestão de usuários, produtos, fornecedores e cestas de compras de forma intuitiva e eficiente.

## Funcionalidades

- **Cadastro de Usuários:** Permite que novos usuários se cadastrem, com senhas armazenadas de forma segura usando hash SHA-256;
- **Autenticação:** Usuários podem fazer login para acessar funcionalidades do sistema;
- **Cadastro de Produtos e Fornecedores:** Usuários podem cadastrar produtos e fornecedores no banco de dados;
- **Controle de produtos e Controle de fornecedores:** Usuários podem Atualizar ou excluir os dados dos produtos e fornecedores;
- **Cesta de Compras:** Selecionar produtos através de checkboxes e visualizar os itens adicionados;
- **Resumo da Cesta:** Área dedicada que exibe a cesta de compras com o valor total e os produtos selecionados;

## Tecnologias Utilizadas

- **Linguagens:** HTML, CSS, PHP;
- **Banco de Dados:** MySQL;
- **Hashing:** SHA-256 para armazenamento seguro de senhas;

## Etapas de Implementação

### Etapa 1 – Análise

Na fase de análise, foram identificadas as seguintes telas e elementos visuais necessários:

1. **Tela de Login:** Formulário para autenticação de usuários;
2. **Tela de Cadastro de Usuário:** Formulário para criação de novos usuários;
3. **Tela de Cadastro de Produtos:** Formulário para adicionar novos produtos;
4. **Tela de Controle de Produtos:** Funcionalidade para editar ou excluir produtos.;
5. **Tela de Cadastro de Fornecedores:** Formulário para adicionar novos fornecedores;
6. **Tela de Controle de Fornecedores:** Funcionalidade para editar ou excluir fornecedores;
7. **Tela de Seleção de Produtos(Homepage):** Exibição de produtos com checkboxes para seleção;
8. **Tela da Cesta de Compras:** Exibição dos produtos selecionados e resumo da compra;

Os esboços das telas foram criados utilizando o [Figma](https://www.figma.com/design/35KO2MhvmSlqYeOHa1pV0s/Biblioteca?node-id=15-26&t=6rTS10C5cUAhp2hT-1).

### Etapa 2 – Modelagem

Um Diagrama Entidade-Relacionamento (DER) foi elaborado para representar os relacionamentos entre as entidades do sistema. As principais entidades incluem:

- **users:** Armazena informações dos usuários cadastrados;
- **product_registration:** Detalhes sobre os produtos disponíveis;
- **supplier_registration:** Informações sobre os fornecedores dos produtos;
- 
![Diagrama ER de banco de dados (Universo Literário)](https://github.com/user-attachments/assets/f5d97d41-30c6-4514-9a6c-73294e451fe4)

## Como Executar o Projeto

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/WellingtonHSL/CRUD-de-Produtos.git
