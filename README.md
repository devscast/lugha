# Lugha - yet another PHP Generative AI Framework

![Lint](https://github.com/devscast/lugha/actions/workflows/lint.yml/badge.svg)
![Test](https://github.com/devscast/lugha/actions/workflows/test.yml/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/devscast/lugha/version)](https://packagist.org/packages/devscast/lugha)
[![Total Downloads](https://poser.pugx.org/devscast/lugha/downloads)](https://packagist.org/packages/devscast/lugha)
[![License](https://poser.pugx.org/devscast/lugha/license)](https://packagist.org/packages/devscast/lugha)

> [!NOTE]  
> Work in progress.

The main idea behind this project is to provide a provider/model agnostic framework for PHP developers to build AI powered applications.
The framework is designed to be modular and extensible, allowing developers to easily add new providers.

This project is highly inspired by [LangChain](https://www.langchain.com/) and [LLPhant](https://github.com/theodo-group/LLPhant/), designed 
for RAG (Retrieval-Augmented Generation) based applications with integration of Embeddings, Completion and Reranking models.

*supported providers:*

| Provider      | Link                                                  | Features                     |
|---------------|-------------------------------------------------------|------------------------------|
| OpenAI        | [openai.com](https://openai.com)                      | Completion, Embeddings       |
| Mistral       | [mistral.ai](https://mistral.ai/)                     | Completion, Embeddings       |
| Google        | [ai.google](https://ai.google/)                       | Completion, Embeddings       |
| GitHub        | [github.com](https://github.com/marketplace/models)   | Completion, Embeddings       |
| Anthropic     | [anthropic.com](https://www.anthropic.com/)           | Completion                   |
| Voyager.ai    | [voyageai.com](https://www.voyageai.com/)             | Embeddings, Reranking        |
| Ollama        | [ollama.com](https://ollama.com/)                     | Completion, Embeddings       |


## Installation
```bash
composer require devscast/lugha
```

## Contributors

<a href="https://github.com/devscast/lugha/graphs/contributors" title="show all contributors">
  <img src="https://contrib.rocks/image?repo=devscast/lugha" alt="contributors"/>
</a>
