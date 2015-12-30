#!/usr/bin/env python

"""
Usage:
  {0:s} -h | --help
  {0:s} (-b PATH | --base-path PATH) <dump-file> [<csv-file>]

Options:
  -h, --help                 - show help;
  -b PATH, --base-path PATH  - set new base path for images.
"""

import re
import docopt
import os.path
import xml.dom.minidom
import base64
import slugify
import csv
import sys

cut_tag_pattern = re.compile('<cut\s*\/>')
image_tag_pattern = re.compile('!\[([^\]]*)\]\(([^\)]+)\)')

def parse_options():
	script_name = os.path.basename(__file__)
	return docopt.docopt(__doc__.format(script_name))

def process_options(options):
	if options['<csv-file>'] == None:
		csv_file = os.path.basename(options['<dump-file>'])
		csv_file = os.path.splitext(csv_file)[0]
		options['<csv-file>'] = csv_file + '.csv'

def parse_parameters():
	options = parse_options()
	process_options(options)

	return options

def read_xml(filename):
	return xml.dom.minidom.parse(filename)

def find_posts(node):
	return node.getElementsByTagName('post')

def get_subnode(node, subnode_tag):
	return node.getElementsByTagName(subnode_tag)[0]

def decode_text(text):
	data = base64.b64decode(text)
	data = data.decode('string_escape')
	return data.strip()

def get_node_text(nodes):
	text = ''
	for node in nodes.childNodes:
		if node.nodeType == node.TEXT_NODE:
			text += decode_text(node.data)

	return text

def extract_excerpt(text):
	excerpt = cut_tag_pattern.split(text)[0]
	return excerpt.strip()

def extract_content(text):
	return cut_tag_pattern.sub('', text)

def correct_path(base_path, path):
	filename = os.path.basename(path)
	return os.path.join(base_path, filename)

def correct_image_tag(base_path, path, alt):
	corrected_path = correct_path(base_path, path)
	return '![{:s}]({:s})'.format(alt, corrected_path)

def get_post_text(post, base_path):
	text = get_node_text(get_subnode(post, 'text'))
	text = image_tag_pattern.sub( \
		lambda match: correct_image_tag( \
			base_path, \
			match.group(2), \
			match.group(1)), \
		text)
	return text

def get_post_tags(post):
	tags = get_node_text(get_subnode(post, 'tags'))
	return '|'.join(map(lambda tag: tag.strip(), tags.split(',')))

def get_attribute(node, attribute_name):
	return node.attributes[attribute_name].value

def correct_timestamp(timestamp):
	return timestamp.replace('T', ' ')

def prepare_post(post, base_path):
	title = get_node_text(get_subnode(post, 'title'))
	text = get_post_text(post, base_path)
	return { \
		'Title': title, \
		'Slug': slugify.slugify(title), \
		'Excerpt': extract_excerpt(text), \
		'Content': extract_content(text), \
		'Tags': get_post_tags(post), \
		'Created date': correct_timestamp(get_attribute(post, 'create-time')), \
		'Updated date': correct_timestamp(get_attribute(post, 'modify-time'))}

def prepare_posts(posts, base_path):
	return map(lambda post: prepare_post(post, base_path), posts)

def write_csv_to_writer(writer, posts):
	field_names = [ \
		'Title', \
		'Slug', \
		'Excerpt', \
		'Content', \
		'Tags', \
		'Created date', \
		'Updated date']
	writer = csv.DictWriter( \
		writer, \
		fieldnames = field_names, \
		lineterminator = '\n')

	writer.writeheader()
	for post in posts:
		writer.writerow(post)

def write_csv_to_file(csv_filename, posts):
	with open(csv_filename, 'w') as csv_file:
		write_csv_to_writer(csv_file, posts)

def write_csv_to_stdout(posts):
	write_csv_to_writer(sys.stdout, posts)

def write_csv(target, posts):
	if target != "-":
		write_csv_to_file(target, posts)
	else:
		write_csv_to_stdout(posts)

parameters = parse_parameters()
dom = read_xml(parameters['<dump-file>'])
posts = find_posts(dom.documentElement)
prepared_posts = prepare_posts(posts, parameters['--base-path'])
write_csv(parameters['<csv-file>'], prepared_posts)
