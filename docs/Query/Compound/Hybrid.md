# Hybrid query

> More info about Hybrid query is in the [official OpenSearch docs][1]

The hybrid query combines multiple search methods into a single query, typically used to blend lexical (keyword) search with semantic (vector) search capabilities. This allows you to leverage both traditional text matching and modern neural search in a single request.

## Basic Usage

To create a hybrid query, instantiate a `HybridQuery` object and add your queries to it:

```JSON
{
    "hybrid": {
        "queries": [
            {
                "neural": {
                    "title_vector": {
                        "query_vector": [0.1, 0.2, 0.3, ...],
                        "k": 10
                    }
                }
            },
            {
                "match": {
                    "title": "search keywords"
                }
            }
        ]
    }
}
```

And now the query via DSL:

```php
use ONGR\ElasticsearchDSL\Query\Compound\HybridQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
// Assuming you have a NeuralQuery class for vector search

$matchQuery = new MatchQuery('title', 'search keywords');
// $neuralQuery = new NeuralQuery('title_vector', $queryVector, ['k' => 10]);

$hybridQuery = new HybridQuery();
$hybridQuery->addQuery($matchQuery);
// $hybridQuery->addQuery($neuralQuery);

$search = new Search();
$search->addQuery($hybridQuery);

$queryArray = $search->toArray();
```

## Constructor with Queries

You can also initialize the hybrid query with an array of queries:

```php
$termQuery = new TermQuery('status', 'published');
$matchQuery = new MatchQuery('content', 'elasticsearch opensearch');

$hybridQuery = new HybridQuery([$termQuery, $matchQuery]);

$search = new Search();
$search->addQuery($hybridQuery);
```

## Method Chaining

The `addQuery()` method returns the hybrid query instance, allowing for method chaining:

```php
$hybridQuery = new HybridQuery();
$hybridQuery
    ->addQuery(new MatchQuery('title', 'search'))
    ->addQuery(new TermQuery('category', 'technology'))
    ->addParameter('boost', 2.0);
```

## Adding Parameters

Like other queries in the DSL, you can add additional parameters to the hybrid query:

```php
$hybridQuery = new HybridQuery();
$hybridQuery->addQuery(new MatchQuery('title', 'opensearch'));
$hybridQuery->addParameter('boost', 1.5);
$hybridQuery->addParameter('_name', 'my_hybrid_query');
```

## Use Cases

Hybrid queries are particularly useful when you want to:

1. Combine traditional keyword search with vector/semantic search
2. Blend different search strategies for better relevance
3. Implement AI-powered search while maintaining support for exact matches
4. Create sophisticated search experiences that leverage multiple ranking signals

[1]: https://docs.opensearch.org/docs/latest/query-dsl/compound/hybrid/
