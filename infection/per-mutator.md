# Effects per Mutator

| Mutator              | Mutations | Killed | Escaped | Errors | Syntax Errors | Timed Out | Skipped | MSI (%s) | Covered MSI (%s) |
| -------------------- | --------- | ------ | ------- | ------ | ------------- | --------- | ------- | -------- | ---------------- |
| ArrayItem            |         2 |      1 |       1 |      0 |             0 |         0 |       0 |    50.00 |            50.00 |
| ArrayItemRemoval     |        33 |     32 |       1 |      0 |             0 |         0 |       0 |    96.97 |            96.97 |
| ArrayOneItem         |         3 |      2 |       1 |      0 |             0 |         0 |       0 |    66.67 |            66.67 |
| CastInt              |        10 |      4 |       6 |      0 |             0 |         0 |       0 |    40.00 |            40.00 |
| Coalesce             |        18 |     17 |       1 |      0 |             0 |         0 |       0 |    94.44 |            94.44 |
| Concat               |        15 |     12 |       3 |      0 |             0 |         0 |       0 |    80.00 |            80.00 |
| ConcatOperandRemoval |        24 |     20 |       4 |      0 |             0 |         0 |       0 |    83.33 |            83.33 |
| DecrementInteger     |        11 |      2 |       9 |      0 |             0 |         0 |       0 |    18.18 |            18.18 |
| FalseValue           |         2 |      2 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
| Identical            |        18 |     13 |       5 |      0 |             0 |         0 |       0 |    72.22 |            72.22 |
| LogicalAnd           |         4 |      3 |       1 |      0 |             0 |         0 |       0 |    75.00 |            75.00 |
| LogicalNot           |         2 |      2 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
| LogicalOr            |         4 |      1 |       3 |      0 |             0 |         0 |       0 |    25.00 |            25.00 |
| MethodCallRemoval    |        88 |     82 |       6 |      0 |             0 |         0 |       0 |    93.18 |            93.18 |
| NotIdentical         |         5 |      5 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
| PublicVisibility     |        37 |     37 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
| TrueValue            |         1 |      1 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
| UnwrapTrim           |         2 |      2 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
| While_               |         9 |      9 |       0 |      0 |             0 |         0 |       0 |   100.00 |           100.00 |
