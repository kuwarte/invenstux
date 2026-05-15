<?php

class CategoryService
{
    private CategoryRepository $categoryRepo;

    public function __construct(PDO $db)
    {
        require_once __DIR__ . '/../Repositories/CategoryRepository.php';
        $this->categoryRepo = new CategoryRepository($db);
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepo->getAll(); // FLAT
    }

    public function getCategoryTree(): array
    {
        $roots = $this->categoryRepo->getRootCategories();
        return array_map(fn($root) => $this->buildTree($root), $roots);
    }

    public function createCategory(array $data): int
    {
        $this->validateCategoryData($data);

        if (!empty($data['parent_id'])) {
            $parent = $this->categoryRepo->findById($data['parent_id']);
            if (!$parent) {
                throw new Exception('Parent category not found');
            }
        }

        return $this->categoryRepo->create($data);
    }

    public function updateCategory(int $id, array $data): void
    {
        $category = $this->categoryRepo->findById($id);

        if (!$category) {
            throw new Exception('Category not found');
        }

        $this->validateCategoryData($data);

        if (!empty($data['parent_id'])) {
            if ($data['parent_id'] == $id) {
                throw new Exception('Category cannot be its own parent');
            }

            $parent = $this->categoryRepo->findById($data['parent_id']);
            if (!$parent) {
                throw new Exception('Parent category not found');
            }
        }

        $this->categoryRepo->update($id, $data);
    }

    public function deleteCategory(int $id): void
    {
        $children = $this->categoryRepo->getChildrenOf($id);

        if (!empty($children)) {
            throw new Exception('Cannot delete category with subcategories');
        }

        $this->categoryRepo->delete($id);
    }

    private function buildTree(array $category): array
    {
        $category['children'] = $this->categoryRepo->getChildrenOf($category['id']);

        foreach ($category['children'] as &$child) {
            $child = $this->buildTree($child);
        }

        return $category;
    }

    private function validateCategoryData(array $data): void
    {
        if (empty($data['name'])) {
            throw new Exception('Category name is required');
        }
    }
}
