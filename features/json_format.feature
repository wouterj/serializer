Feature: Serializing Single Object
    In order to save objects for APIs
    As a developer
    I want to serialize the object in the JSON format

    Scenario: Simple properties
        Given a file called "User.php" with:
            """
            class User
            {
                public $name;
                public $age;
            }
            """
        And a file called "config/user.yml" with:
            """
            User:
                properties:
                    name:
                        type: string
                        map: key
                    age:
                        type: number
            """
        And a "User" object with "name = 'John Doe'" and "age = 32"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            {"John Doe":{"age":32}}
            """

    Scenario: Array properties
        Given a file called "Post.php" with:
            """
            class Post
            {
                public $tags = array();
            }
            """
        And a file called "config/post.yml" with:
            """
            Post:
                properties:
                    tags:
                        type: array
            """
        And a "Post" object with "tags = ['feature', 'json', 'bar']"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            [{"tags":["feature","json","bar"]}]
            """

    Scenario: Typed arrays
        Given a file called "Range.php" with:
            """
            class Range
            {
                public $numbers = array();
            }
            """
        And a file called "config/range.yml" with:
            """
            Range:
                properties:
                    numbers:
                        type: number[]
            """
        And a "Range" object with "numbers = ['12', 10, 8, 6, 4, 2]"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            [{"numbers":[12,10,8,6,4,2]}]
            """

    Scenario: Mapped as value
        Given a file called "Category.php" with:
            """
            class Category
            {
                public $name;
            }
            """
        And a file called "config/category.yml" with:
            """
            Category:
                properties:
                    name:
                        type: string
                        map: value
            """
        And a "Category" object with "name = 'Foo'"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            ["Foo"]
            """

    Scenario: Object
        Given a file called "BlogPost.php" with:
            """
            class BlogPost
            {
                public $tag;
            }
            """
        And a file called "config/blogpost.yml" with:
            """
            BlogPost:
                properties:
                    tag:
                        type: object
            """
        And a file called "Tag.php" with:
            """
            class Tag
            {
                public $name;
            }
            """
        And a file called "config/tag.yml" with:
            """
            Tag:
                properties:
                    name:
                        type: string
                        map: value
            """
        And a "Tag" object with "name = 'Foo'" as "tag"
        And a "BlogPost" object with "tag = %tag%"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            [{"tag":"Foo"}]
            """
