# Lugha - yet another PHP Generative AI Framework

![Lint](https://github.com/devscast/lugha/actions/workflows/lint.yml/badge.svg)
![Test](https://github.com/devscast/lugha/actions/workflows/test.yml/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/devscast/lugha/version)](https://packagist.org/packages/devscast/lugha)
[![Total Downloads](https://poser.pugx.org/devscast/lugha/downloads)](https://packagist.org/packages/devscast/lugha)
[![License](https://poser.pugx.org/devscast/lugha/license)](https://packagist.org/packages/devscast/lugha)

> [!NOTE]  
> Work in progress.

Lugha from Swahili meaning "Language" is a PHP Generative AI Framework that provides a simple and easy way to interact with various AI providers.
The main idea is to provide a unified provider-agnostic API for AI models, making it easier to switch between providers.

This project is highly inspired by [LangChain](https://www.langchain.com/) and [LLPhant](https://github.com/theodo-group/LLPhant/), designed 
for Chatbot, RAG (Retrieval-Augmented Generation) based applications with integration of Embeddings, Completion and Reranking models.

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

## Usage

### Embeddings
Embeddings are a type of word representation that allows words with similar meaning to have a similar representation.
they can be used to find the similarity between words, phrases, or sentences.
useful for document classification, clustering, and information retrieval.

```php
$client = ClientFactory::create(Provider::GOOGLE);
$embeddings = $client->embeddings(
    prompt: 'Hello, world!', 
    config: new EmbeddingsConfig(
        model: 'text-embedding-004',
        dimensions: 512
    )
)->embedding;
```

### Completion
Completion models are designed to generate human-like text based on the input prompt.

```php
$client = ClientFactory::create(Provider::OPENAI);

// from a prompt
$completion = $client->completion(
    input: 'Hello, world!', 
    config: new CompletionConfig(
        model: 'gpt-3.5-turbo',
        temperature: 0.5,
        maxTokens: 100,
        frequencyPenalty: 0.5,
        presencePenalty: 0.5
    )
)->completion;

// from a chat history
$chat = $client->chat(
    input: History::fromMessages([
        new Message('You are a chatbot, expert in philosophy', Role::SYSTEM),
        new Message('what is the meaning of life ?', Role::USER)
    ]),
    config: new ChatConfig(
        model: 'gpt-4-turbo',
        temperature: 0.5,
        maxTokens: 100,
        frequencyPenalty: 0.5,
        presencePenalty: 0.5
    )
)->completion;
```

### Reranking
Reranking models are designed to re-rank a list of documents based on the input prompt.
useful for search engines, recommendation systems, and information retrieval.

```php  
$client = ClientFactory::create(Provider::VOYAGER);

$ranking = $client->reranking(
    prompt: 'What is the meaning of life ?',
    documents: [
        new Document('The best way to predict the future is to create it.')
        new Document('The only way to do great work is to love what you do.')
        new Document('Life is short, smile while you still have teeth.'),
        new Document('The best time to plant a tree was 20 years ago. The second best time is now.')
    ],
    config: new RerankingConfig(
        model: 'voyager-1.0',
        topK: 3
    )
)->documents;
```

## Contributors

<a href="https://github.com/devscast/lugha/graphs/contributors" title="show all contributors">
  <img src="https://contrib.rocks/image?repo=devscast/lugha" alt="contributors"/>
</a>
